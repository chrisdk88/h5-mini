using API.Models.Common;

namespace API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class FriendsController : ControllerBase
    {
        private readonly AppDBContext _context;

        public FriendsController(AppDBContext context)
        {
            _context = context;
        }

        // GET: api/Friends
        [HttpGet]
        public async Task<ActionResult<IEnumerable<Friends>>> GetFriends()
        {
            return await _context.Friends.ToListAsync();
        }

        [Authorize]
        [HttpGet("getUsersFriends/{userId}")]
        public async Task<ActionResult<IEnumerable<FriendDto>>> GetUserFriends(int userId)
        {
            var friends = await _context.Friends
          .Where(f => (f.user1_id == userId || f.user2_id == userId) && f.status)
          .Select(f => new FriendDto
          {
              FriendshipId = f.id,  // The ID from the Friends table
              UserId = f.user1_id == userId ? f.user2_id : f.user1_id,  // The other user's ID
              Username = f.user1_id == userId
                  ? _context.Users.FirstOrDefault(u => u.id == f.user2_id).username
                  : _context.Users.FirstOrDefault(u => u.id == f.user1_id).username
          })
          .ToListAsync();

            return Ok(friends);
        }

        [Authorize]
        [HttpGet("requests/{userId}")]
        public async Task<ActionResult<IEnumerable<GetFriendRequestDto>>> GetFriendRequests(int userId)
        {
            // Get and validate current user
            var currentUserId = User.FindFirstValue(ClaimTypes.NameIdentifier);
            if (!int.TryParse(currentUserId, out int parsedCurrentUserId) || userId != parsedCurrentUserId)
            {
                return Forbid("You can only view your own friend requests");
            }

            var requests = await _context.Friends
                .Where(f => f.user2_id == userId && !f.status)
                .Join(_context.Users,
                    friend => friend.user1_id, 
                    user => user.id,
                    (friend, user) => new
                    {
                        RequestId = friend.id, 
                        SenderId = user.id,
                        SenderUsername = user.username
                    })
                .ToListAsync();

            return Ok(requests);
        }

        [Authorize]
        [HttpGet("Search")]
        public async Task<ActionResult<IEnumerable<object>>> SearchUsers([FromQuery] string query)
        {
            // Get the current user's username from the JWT token
            var currentUsername = User.FindFirst("name")?.Value;

            if (string.IsNullOrWhiteSpace(query))
                return BadRequest("Query cannot be empty.");

            var currentUserIdStr = User.FindFirstValue(ClaimTypes.NameIdentifier);
            if (!int.TryParse(currentUserIdStr, out var currentUserId))
                return Unauthorized("Invalid user ID in token.");

            var friendIds = await _context.Friends
                .Where(f =>
                    (f.user1_id == currentUserId || f.user2_id == currentUserId) &&
                    f.status == true)
                .Select(f => f.user1_id == currentUserId ? f.user2_id : f.user1_id)
                .ToListAsync();

            // Add self to exclusion list
            friendIds.Add(currentUserId);

            var users = await _context.Users
                .Where(u => u.username.Contains(query) && !friendIds.Contains(u.id))
                .Select(u => new { u.username, u.id })
                .ToListAsync();

            if (!users.Any())
                return NotFound("No users match the search query.");

            return Ok(users);
        }

        [Authorize]
        // PUT: api/Friends/5
        [HttpPut("acceptFriendRequest/{id}")]
        public async Task<IActionResult> UpdateFriendStatus(int id, [FromBody] bool status)
        {
            var friend = await _context.Friends.FindAsync(id);

            if (friend == null)
            {
                return NotFound($"Friend record with ID {id} not found.");
            }

            friend.status = true;
            friend.updated_at = DateTime.UtcNow;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                return StatusCode(500, "A concurrency error occurred while updating the friend status.");
            }

            return NoContent();
        }


        [Authorize]
        [HttpPost("sendFriendRequest")]
        public async Task<ActionResult<Friends>> PostFriends(AddFriend friends)
        {
            var userIdClaim = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdClaim, out int userId))
            {
                return Unauthorized("User ID is missing or invalid.");
            }

            if (userId == friends.user2_id)
            {
                return BadRequest("You cannot send a friend request to yourself.");
            }

            // Check if a friend request already exists in either direction
            var existingRequest = await _context.Friends.FirstOrDefaultAsync(f =>
                (f.user1_id == userId && f.user2_id == friends.user2_id) ||
                (f.user1_id == friends.user2_id && f.user2_id == userId));

            if (existingRequest != null)
            {
                return BadRequest("Friend request already exists or you are already friends.");
            }

            Friends newFriend = new() { 
                user1_id = userId,
                user2_id = friends.user2_id,
                status = false, 
                updated_at = DateTime.UtcNow,
                created_at = DateTime.UtcNow,
            };
            _context.Friends.Add(newFriend);
            await _context.SaveChangesAsync();

            return Ok(newFriend);
        }

        // DELETE: api/Friends/5
        [Authorize]
        [HttpDelete("decline/{id}")]
        public async Task<IActionResult> DeleteFriends(int id)
        {
            var friends = await _context.Friends.FindAsync(id);
            if (friends == null)
            {
                return NotFound();
            }

            _context.Friends.Remove(friends);
            await _context.SaveChangesAsync();

            return NoContent();
        }

      
    }
}
