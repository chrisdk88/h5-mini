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

        // GET: api/Scores/user/5
        [HttpGet("usersScore/{userId}")]
        public async Task<ActionResult<IEnumerable<Score>>> GetScoresByUserId(int userId)
        {
            var scores = await _context.Score
                .Where(s => s.user_id == userId)
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
