using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using API.Data;
using API.Models;
using Microsoft.AspNetCore.Http.HttpResults;

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
        [HttpGet]
        public async Task<ActionResult<IEnumerable<WordleSession>>> GetWordleSessions()
        {
            return await _context.WordleSessions.ToListAsync();
        }

        // GET: api/WordleSessions/5
        [HttpGet("{id}")]
        public async Task<ActionResult<WordleSession>> GetWordleSession(int id)
        {
            var wordleSession = await _context.WordleSessions.FindAsync(id);

            if (wordleSession == null)
            {
                return NotFound();
            }

            return wordleSession;
        }
        
        [HttpPost]
        public async Task<ActionResult<WordleSession>> PostWordleSession(PostWordSession wordleSession)
        {
            WordleSession newWordleSession = new()
            {
                player1_id = wordleSession.player1_id,
                player2_id = wordleSession.player2_id,
                word_id = wordleSession.word_id,
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
        [HttpDelete("{id}")]
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
