namespace API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class LeaderboardsController : ControllerBase
    {
        private readonly AppDBContext _context;

        public LeaderboardsController(AppDBContext context)
        {
            _context = context;
        }

        [HttpGet("leaderboard")]
        public async Task<ActionResult<IEnumerable<object>>> GetLeaderboard()
        {
            // First: group scores by user_id and get total_score
            var leaderboardData = await _context.Score
                .GroupBy(s => s.user_id)
                .Select(g => new
                {
                    user_id = g.Key,
                    total_score = g.Sum(s => s.points)
                })
                .OrderByDescending(e => e.total_score)
                .ToListAsync();

            // Second: fetch all users that appear in the leaderboard
            var userIds = leaderboardData.Select(l => l.user_id).ToList();

            var users = await _context.Users
                .Where(u => userIds.Contains(u.id))
                .ToDictionaryAsync(u => u.id, u => u.username);

            // Third: combine data and add position
            var ranked = leaderboardData
                .Select((entry, index) => new
                {
                    position = index + 1,
                    username = users.ContainsKey(entry.user_id) ? users[entry.user_id] : "Unknown",
                    totalScore = entry.total_score
                });

            return Ok(ranked);
        }

    }
}
