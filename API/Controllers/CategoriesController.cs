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
    public class CategoriesController : ControllerBase
    {
        private readonly AppDBContext _context;

        public CategoriesController(AppDBContext context)
        {
            _context = context;
        }

        // GET: api/Categories
        [HttpGet]
        public async Task<ActionResult<IEnumerable<Category>>> GetCategories()
        {
            return await _context.Categories.ToListAsync();
        }

        // GET: api/Categories/5
        [HttpGet("{id}")]
        public async Task<ActionResult<Category>> GetCategory(int id)
        {
            var category = await _context.Categories.FindAsync(id);

            if (category == null)
            {
                return NotFound();
            }

            return category;
        }

        [Authorize]
        [HttpPut("editCategory/{id}")]
        public async Task<IActionResult> PutCategory(int id, Category category)
        {
            if (id != category.id)
            {
                return BadRequest();
            }

            _context.Entry(category).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!CategoryExists(id))
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

        [Authorize(Roles = "Admin")]
        [HttpPost("postCategory")]
        public async Task<ActionResult<Category>> postCategory(postCategory category)
        {
            bool exists = await _context.Categories
         .AnyAsync(c => c.category.ToLower() == category.category.ToLower());

            if (exists)
            {
                return Conflict("A category with this name already exists.");
            }


            if (category == null)
            {
                return BadRequest("category can not be empty");
            }

            Category newCategory = new()
            {
                category = category.category,
                created_at = DateTime.UtcNow,
                updated_at = DateTime.UtcNow,
            };

            _context.Categories.Add(newCategory);
            await _context.SaveChangesAsync();

            return Ok(newCategory);
        }

        // DELETE: api/Categories/5
        [Authorize]
        [HttpDelete("deleteCategory/{id}")]
        public async Task<IActionResult> DeleteCategory(int id)
        {
            var category = await _context.Categories.FindAsync(id);
            if (category == null)
            {
                return NotFound();
            }

            _context.Categories.Remove(category);
            await _context.SaveChangesAsync();

            return NoContent();
        }

        private bool CategoryExists(int id)
        {
            return _context.Categories.Any(e => e.id == id);
        }
    }
}
