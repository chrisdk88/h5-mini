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
    public class LolChampionsController : ControllerBase
    {
        private readonly AppDBContext _context;

        public LolChampionsController(AppDBContext context)
        {
            _context = context;
        }

        // GET: api/LolChampions
        [HttpGet]
        public async Task<ActionResult<IEnumerable<LoldleChampions>>> GetLolChampions()
        {
            return await _context.LolChampions.ToListAsync();
        }

        // GET: api/LolChampions/5
        [HttpGet("{id}")]
        public async Task<ActionResult<LoldleChampions>> GetLolChampions(int id)
        {
            var lolChampions = await _context.LolChampions.FindAsync(id);

            if (lolChampions == null)
            {
                return NotFound();
            }

            return lolChampions;
        }

        // PUT: api/LolChampions/5
        // To protect from overposting attacks, see https://go.microsoft.com/fwlink/?linkid=2123754
        [HttpPut("{id}")]
        public async Task<IActionResult> PutLolChampions(int id, LoldleChampions lolChampions)
        {
            if (id != lolChampions.id)
            {
                return BadRequest();
            }

            _context.Entry(lolChampions).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!LolChampionsExists(id))
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

        // POST: api/LolChampions
        // To protect from overposting attacks, see https://go.microsoft.com/fwlink/?linkid=2123754
        [HttpPost]
        public async Task<ActionResult<LoldleChampions>> PostLolChampions(LoldleChampions lolChampions)
        {
            _context.LolChampions.Add(lolChampions);
            await _context.SaveChangesAsync();

            return CreatedAtAction("GetLolChampions", new { id = lolChampions.id }, lolChampions);
        }

        // DELETE: api/LolChampions/5
        [HttpDelete("{id}")]
        public async Task<IActionResult> DeleteLolChampions(int id)
        {
            var lolChampions = await _context.LolChampions.FindAsync(id);
            if (lolChampions == null)
            {
                return NotFound();
            }

            _context.LolChampions.Remove(lolChampions);
            await _context.SaveChangesAsync();

            return NoContent();
        }

        private bool LolChampionsExists(int id)
        {
            return _context.LolChampions.Any(e => e.id == id);
        }
    }
}
