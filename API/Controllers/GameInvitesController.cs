using System;
using System.Collections.Generic;
using System.Linq;
namespace API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class GameInvitesController : ControllerBase
    {
        private readonly AppDBContext _context;

        public GameInvitesController(AppDBContext context)
        {
            _context = context;
        }

        // GET: api/GameInvites
        [HttpGet]
        public async Task<ActionResult<IEnumerable<GameInvites>>> GetGameInvites()
        {
            return await _context.GameInvites.ToListAsync();
        }

        // GET: api/GameInvites/5
        [Authorize]
        [HttpGet("getGameInvite/{id}")]
        public async Task<ActionResult<GameInvites>> GetGameInvites(int id)
        {
            var gameInvites = await _context.GameInvites.FindAsync(id);

            if (gameInvites == null)
            {
                return NotFound();
            }

            return gameInvites;
        }

        [Authorize]
        [HttpPut("editGameInvite{id}")]
        public async Task<IActionResult> PutGameInvites(int id, GameInvites gameInvites)
        {
            if (id != gameInvites.id)
            {
                return BadRequest();
            }

            _context.Entry(gameInvites).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!GameInvitesExists(id))
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
        [HttpPost("sendGameInvite")]
        public async Task<ActionResult<GameInvites>> PostGameInvites(postGameInvite gameInvites)
        {
            GameInvites newGameInvite = new()
            {
                player1_id = gameInvites.player1_id,
                player2_id = gameInvites.player2_id,
                status = gameInvites.status,
                game_type = gameInvites.game_type,
                game_session_id = gameInvites.game_session_id,
                created_at = DateTime.Now,
                updated_at = DateTime.Now,
            };

            _context.GameInvites.Add(newGameInvite);
            await _context.SaveChangesAsync();

            return Ok(newGameInvite);
        }

        // DELETE: api/GameInvites/5
        [HttpDelete("decline/{id}")]
        public async Task<IActionResult> DeleteGameInvites(int id)
        {
            var gameInvites = await _context.GameInvites.FindAsync(id);
            if (gameInvites == null)
            {
                return NotFound();
            }

            _context.GameInvites.Remove(gameInvites);
            await _context.SaveChangesAsync();

            return NoContent();
        }

        private bool GameInvitesExists(int id)
        {
            return _context.GameInvites.Any(e => e.id == id);
        }
    }
}
