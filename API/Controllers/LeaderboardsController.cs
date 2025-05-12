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

        // GET: api/Leaderboards/leaderboard
        [Authorize]
        [HttpGet]
        public async Task<ActionResult<IEnumerable<object>>> GetLeaderboard()
        {
            var now = DateTime.UtcNow;
            var today = now.Date;
            var oneWeekAgo = now.Date.AddDays(-7);

            // method to fetch leaderboard by time filter
            async Task<List<(int userId, int totalScore)>> GetLeaderboardData(DateTime? fromDate = null)
            {
                var query = _context.Score.AsQueryable();

                if (fromDate.HasValue)
                {
                    query = query.Where(s => s.created_at >= fromDate.Value);
                }

                var data = await query
                    .GroupBy(s => s.user_id)
                    .Select(g => new { userId = g.Key, totalScore = g.Sum(s => s.points) })
                    .OrderByDescending(e => e.totalScore)
                    .ToListAsync();

                // Convert anonymous objects to tuples
                return data.Select(x => (x.userId, x.totalScore)).ToList();
            }


            var allTimeData = await GetLeaderboardData();
            var dailyData = await GetLeaderboardData(today);
            var weeklyData = await GetLeaderboardData(oneWeekAgo);

            // Combine with usernames and add position
            async Task<List<object>> FormatLeaderboard(List<(int userId, int totalScore)> data)
            {
                var userIds = data.Select(x => x.userId).ToList();

                var users = await _context.Users
                    .Where(u => userIds.Contains(u.id) && !u.banned)
                    .ToDictionaryAsync(u => u.id, u => u.username);

                return data
                    .Where(entry => users.ContainsKey(entry.userId))
                    .Select((entry, index) => new
                    {
                        position = index + 1,
                        username = users[entry.userId],
                        totalScore = entry.totalScore
                    })
                    .ToList<object>();
            }

            var response = new
            {
                allTime = await FormatLeaderboard(allTimeData),
                weekly = await FormatLeaderboard(weeklyData),
                daily = await FormatLeaderboard(dailyData)
            };

            return Ok(response);
        }
    }
}
