namespace API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class ScoresController : ControllerBase
    {
        private readonly AppDBContext _context;

        public ScoresController(AppDBContext context)
        {
            _context = context;
        }

        // GET: api/Scores
        [HttpGet]
        public async Task<ActionResult<IEnumerable<Score>>> GetScore()
        {
            return await _context.Score.ToListAsync();
        }

        // GET: api/Scores
        // GET: api/Scores/getAllUsersTotalPoints/5
        [HttpGet("getAllUsersTotalPoints/{userid}")]
        public async Task<ActionResult<object>> GetUserTotalPoints(int userid)
        {
            var totalPoints = await _context.Score
                .Where(s => s.user_id == userid)
                .SumAsync(s => s.points);

            return Ok(new {total_points = totalPoints });
        }



        // GET: api/Scores/usersScoreSummary/5
        [Authorize]
        [HttpGet("usersScoreSummary/{userId}")]
        public async Task<ActionResult<IEnumerable<object>>> GetScoresSummaryByUserId(int userId)
        {
            var scores = await _context.Score
                .Where(s => s.user_id == userId)
                .Select(s => new
                {
                    s.word,
                    s.attempts,
                    s.game_mode,
                    s.points,
                    game_time = s.game_time.ToString("HH:mm:ss")
                })
                .ToListAsync();

            if (scores == null || scores.Count == 0)
            {
                return NotFound($"No scores found for user with ID {userId}.");
            }

            return Ok(scores);
        }



        // PUT: api/Scores/5
        // To protect from overposting attacks, see https://go.microsoft.com/fwlink/?linkid=2123754
        [HttpPut("editScore/{id}")]
        public async Task<IActionResult> PutScore(int id, Score score)
        {
            if (id != score.id)
            {
                return BadRequest();
            }

            _context.Entry(score).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!ScoreExists(id))
                {
                    return NotFound();
                }
                else
                {
                    throw;
                }
            }

            return NoContent();
        }

        [Authorize]
        [HttpPost("postScore")]
        public async Task<ActionResult<Score>> PostScore(postScore score)
        {
            var userIdClaim = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdClaim, out int userId))
            {
                return Unauthorized("User ID is missing or invalid.");
            }

            Score newScore = new()
            {
                user_id = userId,
                game_type = score.game_type,
                points = score.points,
                game_mode = score.game_mode,
                is_multiplayer = score.is_multiplayer,
                game_session_id = score.game_session_id,
                game_time = new TimeOnly(score.game_time.hour, score.game_time.minute, score.game_time.second),
                attempts = score.attempts,
                word = score.word,
                updated_at = DateTime.UtcNow,
                created_at = DateTime.UtcNow
            };

            _context.Score.Add(newScore);
            await _context.SaveChangesAsync();

            return Ok();
        }

        // DELETE: api/Scores/5
        [HttpDelete("deleteScore/{id}")]
        public async Task<IActionResult> DeleteScore(int id)
        {
            var score = await _context.Score.FindAsync(id);
            if (score == null)
            {
                return NotFound();
            }

            _context.Score.Remove(score);
            await _context.SaveChangesAsync();

            return NoContent();
        }

        private bool ScoreExists(int id)
        {
            return _context.Score.Any(e => e.id == id);
        }
    }
}
