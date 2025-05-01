using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using API.Data;
using API.Models;

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

        // PUT: api/WordleWords/5
        // To protect from overposting attacks, see https://go.microsoft.com/fwlink/?linkid=2123754
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

        // POST: api/WordleWords
        [HttpPost]
        public async Task<ActionResult<WordleWords>> PostWordleWords(WordleWords wordleWords)
        {
            _context.WordleWords.Add(wordleWords);
            await _context.SaveChangesAsync();

            return CreatedAtAction("GetWordleWords", new { id = wordleWords.id }, wordleWords);
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
