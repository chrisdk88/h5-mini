namespace API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class WordleSessionsController : ControllerBase
    {
        private readonly AppDBContext _context;

        public WordleSessionsController(AppDBContext context)
        {
            _context = context;
        }

        // GET: api/WordleSessions
        [Authorize]
        [HttpGet("getAllWordleSessions")]
        public async Task<ActionResult<IEnumerable<WordleSession>>> GetWordleSessions()
        {
            return await _context.WordleSessions.ToListAsync();
        }

        // GET: api/WordleSessions/5
        [Authorize]
        [HttpGet("getWordleSession{id}")]
        public async Task<ActionResult<WordleSession>> GetWordleSession(int id)
        {
            var wordleSession = await _context.WordleSessions.FindAsync(id);

            if (wordleSession == null)
            {
                return NotFound();
            }

            return wordleSession;
        }

        [Authorize]
        [HttpPost("postWordleSession")]
        public async Task<ActionResult<WordleSession>> PostWordleSession(PostWordSession wordleSession)
        {
            int count = await _context.WordleWords.CountAsync();
            int index = new Random().Next(count);

            var randomWord = await _context.WordleWords
                .Skip(index)
                .FirstOrDefaultAsync();

            WordleSession newWordleSession = new()
            {
                player1_id = wordleSession.player1_id,
                player2_id = wordleSession.player2_id,
                word_id = randomWord.id,
                winner_id = wordleSession.winner_id,
                DateOnly = wordleSession.DateOnly,
                created_at = DateTime.Now,
                updated_at = DateTime.Now,
            };
            _context.WordleSessions.Add(newWordleSession);
            await _context.SaveChangesAsync();
            return Ok(newWordleSession);
        }

        // DELETE: api/WordleSessions/5
        [HttpDelete("deleteWordleSession/{id}")]
        public async Task<IActionResult> DeleteWordleSession(int id)
        {
            var wordleSession = await _context.WordleSessions.FindAsync(id);
            if (wordleSession == null)
            {
                return NotFound();
            }

            _context.WordleSessions.Remove(wordleSession);
            await _context.SaveChangesAsync();

            return NoContent();
        }

        private bool WordleSessionExists(int id)
        {
            return _context.WordleSessions.Any(e => e.id == id);
        }
    }
}
