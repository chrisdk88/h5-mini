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
    public class LolHeroesController : ControllerBase
    {
        private readonly AppDBContext _context;

        public LolHeroesController(AppDBContext context)
        {
            _context = context;
        }

        // GET: api/LolHeroes
        [HttpGet]
        public async Task<ActionResult<IEnumerable<LolHeroes>>> GetLolHeroes()
        {
            return await _context.LolHeroes.ToListAsync();
        }

        // GET: api/LolHeroes/5
        [HttpGet("{id}")]
        public async Task<ActionResult<LolHeroes>> GetLolHeroes(int id)
        {
            var lolHeroes = await _context.LolHeroes.FindAsync(id);

            if (lolHeroes == null)
            {
                return NotFound();
            }

            return lolHeroes;
        }

        // PUT: api/LolHeroes/5
        // To protect from overposting attacks, see https://go.microsoft.com/fwlink/?linkid=2123754
        [HttpPut("{id}")]
        public async Task<IActionResult> PutLolHeroes(int id, LolHeroes lolHeroes)
        {
            if (id != lolHeroes.id)
            {
                return BadRequest();
            }

            _context.Entry(lolHeroes).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!LolHeroesExists(id))
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

        // POST: api/LolHeroes
        // To protect from overposting attacks, see https://go.microsoft.com/fwlink/?linkid=2123754
        [HttpPost]
        public async Task<ActionResult<LolHeroes>> PostLolHeroes(LolHeroes lolHeroes)
        {
            _context.LolHeroes.Add(lolHeroes);
            await _context.SaveChangesAsync();

            return CreatedAtAction("GetLolHeroes", new { id = lolHeroes.id }, lolHeroes);
        }

        // DELETE: api/LolHeroes/5
        [HttpDelete("{id}")]
        public async Task<IActionResult> DeleteLolHeroes(int id)
        {
            var lolHeroes = await _context.LolHeroes.FindAsync(id);
            if (lolHeroes == null)
            {
                return NotFound();
            }

            _context.LolHeroes.Remove(lolHeroes);
            await _context.SaveChangesAsync();

            return NoContent();
        }

        private bool LolHeroesExists(int id)
        {
            return _context.LolHeroes.Any(e => e.id == id);
        }
    }
}
