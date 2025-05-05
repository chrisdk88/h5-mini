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
        // GET: api/Friends/User/5
        [HttpGet("{userId}")]
        public async Task<ActionResult<IEnumerable<Friends>>> GetUserFriends(int userId)
        {
            var friends = await _context.Friends
                .Where(f => (f.user1_id == userId || f.user2_id == userId) && f.status)
                .ToListAsync();

            if (friends == null || !friends.Any())
            {
                return NotFound($"No confirmed friends");
            }

            return Ok(friends);
        }

        [Authorize]
        [HttpGet("Search")]
        public async Task<ActionResult<IEnumerable<object>>> SearchUsers([FromQuery] string query)
        {
            // Get the current user's username from the JWT token
            var currentUsername = User.FindFirstValue(ClaimTypes.Name);

            if (string.IsNullOrWhiteSpace(query))
                return BadRequest("Query cannot be empty.");

            var users = await _context.Users
                .Where(u => u.username.Contains(query) && u.username != currentUsername)
                .Select(u => new { u.username, u.id }) 
                .ToListAsync();

            if (!users.Any())
                return NotFound("No users match the search query.");

            return Ok(users);
        }

        [Authorize]
        // PUT: api/Friends/5
        [HttpPut("{id}")]
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
        [HttpPost]
        public async Task<ActionResult<Friends>> PostFriends(AddFriend friends)
        {
            var userIdClaim = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdClaim, out int userId))
            {
                return Unauthorized("User ID is missing or invalid.");
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
        [HttpDelete("{id}")]
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

        private bool FriendsExists(int id)
        {
            return _context.Friends.Any(e => e.id == id);
        }
    }
}
