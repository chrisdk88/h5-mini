namespace API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class WordleWordsController : ControllerBase
    {
        private readonly AppDBContext _context;

        public WordleWordsController(AppDBContext context)
        {
            _context = context;
        }

        // GET: api/WordleWords/random
        [HttpGet("random")]
        public async Task<ActionResult<WordleWords>> GetRandomWordleWord()
        {
            int count = await _context.WordleWords.CountAsync();

            if (count == 0)
            {
                return NotFound("No words found.");
            }

            int index = new Random().Next(count); 

            var randomWord = await _context.WordleWords
                .Skip(index)
                .FirstOrDefaultAsync();

            return Ok(randomWord);
        }

        // GET: api/WordleWords
        [HttpGet("getWordFromCategoryId")]
        public async Task<ActionResult<IEnumerable<WordleWords>>> getRandomWordFromCategoryId()
        {
            return await _context.WordleWords.ToListAsync();
        }

        // GET: api/WordleWords
        [HttpGet]
        public async Task<ActionResult<IEnumerable<WordleWords>>> GetWordleWords()
        {
            return await _context.WordleWords.ToListAsync();
        }

        // GET: api/WordleWords/5
        [HttpGet("{id}")]
        public async Task<ActionResult<WordleWords>> GetWordleWords(int id)
        {
            var wordleWords = await _context.WordleWords.FindAsync(id);

            if (wordleWords == null)
            {
                return NotFound();
            }

            return wordleWords;
        }

 
        [HttpPut("{id}")]
        public async Task<IActionResult> PutWordleWords(int id, WordleWords wordleWords)
        {
            if (id != wordleWords.id)
            {
                return BadRequest();
            }

            _context.Entry(wordleWords).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!WordleWordsExists(id))
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


        [HttpPost]
        public async Task<ActionResult<WordleWords>> PostWordleWords(PostWord wordleWords)
        {
            if (wordleWords.category_id == null)
            {
                return BadRequest("Category ID cannot be null.");
            }

            var categoryExists = await _context.Categories
                .AnyAsync(c => c.id == wordleWords.category_id);

            if (!categoryExists)
            {
                return NotFound($"Category with ID {wordleWords.category_id} does not exist.");
            }

            WordleWords newWord = new()
            {
                word = wordleWords.word,
                category_id = wordleWords.category_id,
            };

            _context.WordleWords.Add(newWord);
            await _context.SaveChangesAsync();

            return Ok(newWord);
        }


        // DELETE: api/WordleWords/5
        [HttpDelete("{id}")]
        public async Task<IActionResult> DeleteWordleWords(int id)
        {
            var wordleWords = await _context.WordleWords.FindAsync(id);
            if (wordleWords == null)
            {
                return NotFound();
            }

            _context.WordleWords.Remove(wordleWords);
            await _context.SaveChangesAsync();

            return NoContent();
        }

        private bool WordleWordsExists(int id)
        {
            return _context.WordleWords.Any(e => e.id == id);
        }
    }
}
