using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using API.Data;
using API.Models.Loldle;

namespace API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class LolSessionsController : ControllerBase
    {
        private readonly AppDBContext _context;

        public LolSessionsController(AppDBContext context)
        {
            _context = context;
        }

        // GET: api/LolSessions
        [HttpGet]
        public async Task<ActionResult<IEnumerable<GamedleSession>>> GetLolSessions()
        {
            return await _context.LolSessions.ToListAsync();
        }

        // GET: api/LolSessions/5
        [HttpGet("{id}")]
        public async Task<ActionResult<GamedleSession>> GetLolSession(int id)
        {
            var lolSession = await _context.LolSessions.FindAsync(id);

            if (lolSession == null)
            {
                return NotFound();
            }

            return lolSession;
        }

        // PUT: api/LolSessions/5
        // To protect from overposting attacks, see https://go.microsoft.com/fwlink/?linkid=2123754
        [HttpPut("{id}")]
        public async Task<IActionResult> PutLolSession(int id, GamedleSession lolSession)
        {
            if (id != lolSession.id)
            {
                return BadRequest();
            }

            _context.Entry(lolSession).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!LolSessionExists(id))
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
        public async Task<ActionResult<GamedleSession>> PostLolSession(PostLolSession lolSession)
        {
            GamedleSession newLolSession = new() { 
                player1_id = lolSession.player1_id,
                player2_id = lolSession.player2_id,
                champion_id = lolSession.champion_id,
                winner_id = lolSession.winner_id,
            };
            _context.LolSessions.Add(newLolSession);
            await _context.SaveChangesAsync();

            return Ok(newLolSession);
        }

        // DELETE: api/LolSessions/5
        [HttpDelete("{id}")]
        public async Task<IActionResult> DeleteLolSession(int id)
        {
            var lolSession = await _context.LolSessions.FindAsync(id);
            if (lolSession == null)
            {
                return NotFound();
            }

            _context.LolSessions.Remove(lolSession);
            await _context.SaveChangesAsync();

            return NoContent();
        }

        private bool LolSessionExists(int id)
        {
            return _context.LolSessions.Any(e => e.id == id);
        }
    }
}
