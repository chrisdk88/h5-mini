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
    public class LeaderboardsController : ControllerBase
    {
        private readonly AppDBContext _context;

        public LeaderboardsController(AppDBContext context)
        {
            _context = context;
        }

        // GET: api/Leaderboards
        [HttpGet]
        public async Task<ActionResult<IEnumerable<Leaderboard>>> Getleaderboards()
        {
            return await _context.leaderboards.ToListAsync();
        }

        // GET: api/Leaderboards/5
        [HttpGet("{id}")]
        public async Task<ActionResult<Leaderboard>> GetLeaderboard(int id)
        {
            var leaderboard = await _context.leaderboards.FindAsync(id);

            if (leaderboard == null)
            {
                return NotFound();
            }

            return leaderboard;
        }

        // PUT: api/Leaderboards/5
        [HttpPut("{id}")]
        public async Task<IActionResult> PutLeaderboard(int id, Leaderboard leaderboard)
        {
            if (id != leaderboard.id)
            {
                return BadRequest();
            }

            _context.Entry(leaderboard).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!LeaderboardExists(id))
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

        // POST: api/Leaderboards
        [HttpPost]
        public async Task<ActionResult<Leaderboard>> PostLeaderboard(Leaderboard leaderboard)
        {
            _context.leaderboards.Add(leaderboard);
            await _context.SaveChangesAsync();

            return CreatedAtAction("GetLeaderboard", new { id = leaderboard.id }, leaderboard);
        }

        // DELETE: api/Leaderboards/5
        [HttpDelete("{id}")]
        public async Task<IActionResult> DeleteLeaderboard(int id)
        {
            var leaderboard = await _context.leaderboards.FindAsync(id);
            if (leaderboard == null)
            {
                return NotFound();
            }

            _context.leaderboards.Remove(leaderboard);
            await _context.SaveChangesAsync();

            return NoContent();
        }

        private bool LeaderboardExists(int id)
        {
            return _context.leaderboards.Any(e => e.id == id);
        }
    }
}
