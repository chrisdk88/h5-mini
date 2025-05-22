using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using API.Models.Wordle;

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

        [Authorize]
        [HttpGet]
        public async Task<ActionResult<IEnumerable<WordleWords>>> GetWordleWords()
        {
            return await _context.WordleWords.ToListAsync();
        }

        [Authorize]
        // GET: api/WordleWords/random
        [HttpGet("getRandomWord")]
        public async Task<ActionResult<object>> GetRandomWordleWord()
        {
            int count = await _context.WordleWords.CountAsync();

            if (count == 0)
                return NotFound("No words found.");

            int index = new Random().Next(count);

            var randomWord = await _context.WordleWords
                .Skip(index)
                .Select(w => new { word = w.word })
                .FirstOrDefaultAsync();

            return Ok(randomWord);
        }


        [Authorize]
        [HttpGet("getRandomDailyWord")]
        public async Task<ActionResult<object>> GetRandomDailyWordleWord()
        {
            var recentWordIds = WordTracker.LastUsedWordIds;

            var eligibleWords = await _context.WordleWords
                .Where(w => !recentWordIds.Contains(w.id))
                .ToListAsync();

            if (!eligibleWords.Any())
                return NotFound("No eligible words found (all recently used).");

            var random = new Random();
            var selectedWord = eligibleWords[random.Next(eligibleWords.Count)];

            // Insert new word at the beginning of the list
            WordTracker.LastUsedWordIds.Insert(0, selectedWord.id);

            // Keep only the last 7 used
            if (WordTracker.LastUsedWordIds.Count > 7)
            {
                WordTracker.LastUsedWordIds.RemoveAt(7);
            }

            return Ok(new { word = selectedWord.word });
        }

        [Authorize]
        // GET: api/WordleWords/getWordFromCategoryId?categoryId=1
        [HttpGet("getWordFromCategoryId/{categoryId}")]
        public async Task<ActionResult<WordleWords>> GetRandomWordFromCategoryId(int categoryId)
        {
            var wordsInCategory = await _context.WordleWords
                .Where(w => w.category_id == categoryId)
                .ToListAsync();

            if (wordsInCategory == null || wordsInCategory.Count == 0)
                return NotFound($"No words found for category ID {categoryId}.");

            var randomWord = wordsInCategory[new Random().Next(wordsInCategory.Count)];

            return Ok(randomWord);
        }

        // PUT: api/WordleWords/5
        [HttpPut("editWord{id}")]
        public async Task<IActionResult> PutWordleWords(int id, WordleWords wordleWords)
        {
            if (id != wordleWords.id)
                return BadRequest();

            _context.Entry(wordleWords).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!WordleWordsExists(id))
                    return NotFound();
                else
                    throw;
            }

            return NoContent();
        }

        // POST: api/WordleWords
        [Authorize(Roles = "Admin")]
        [HttpPost("postWord")]
        public async Task<ActionResult<WordleWords>> PostWordleWords(PostWord wordleWords)
        {
            if (wordleWords.category_id == null)
            {
                return BadRequest("Category ID cannot be null.");
            }

            // Check if the word already exists in this category
            var wordExists = await _context.WordleWords
                .AnyAsync(w => w.word.ToLower() == wordleWords.word.ToLower()
                            && w.category_id == wordleWords.category_id);

            if (wordExists)
            {
                return Conflict($"The word '{wordleWords.word}' already exists in this category.");
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
                created_at = DateTime.UtcNow,
                updated_at = DateTime.UtcNow
            };

            _context.WordleWords.Add(newWord);
            await _context.SaveChangesAsync();

            return Ok(newWord);
        }

        // DELETE: api/WordleWords/5
        [Authorize]
        [HttpDelete("deleteWord/{id}")]
        public async Task<IActionResult> DeleteWordleWords(int id)
        {
            var wordleWords = await _context.WordleWords.FindAsync(id);
            if (wordleWords == null)
                return NotFound();

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
