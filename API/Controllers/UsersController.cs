using System.Configuration;

namespace API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class UsersController : ControllerBase
    {
        private readonly AppDBContext _context;
        private readonly IConfiguration _configuration;


        public UsersController(AppDBContext context, IConfiguration configuration)
        {
            _context = context;
            _configuration = configuration;

        }

        // GET: api/Users
        [HttpGet]
        public async Task<ActionResult<IEnumerable<User>>> GetUsers()
        {
            return await _context.Users.ToListAsync();
        }

        // GET: api/Users/5
        [HttpGet("{id}")]
        public async Task<ActionResult<User>> GetUser(int id)
        {
            var user = await _context.Users.FindAsync(id);

            if (user == null)
            {
                return NotFound();
            }

            return user;
        }

        [HttpPut("Ban/{userid}")]
        public async Task<IActionResult> banUser(int userid, User user)
        {
          
            return NoContent();
        }

        [HttpPut("{id}")]
        public async Task<IActionResult> PutUser(int id, User user)
        {
            if (id != user.id)
            {
                return BadRequest();
            }

            _context.Entry(user).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!UserExists(id))
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

        [HttpPost("Signup")]
        public async Task<ActionResult<User>> signup(Signup signup)
        {

            var HashedPassword = BCrypt.Net.BCrypt.HashPassword(signup.password);

            User user = new()
            {
                email = signup.email,
                username = signup.username,
                hashed_password = HashedPassword,
                role = signup.role,
                updated_at = DateTime.UtcNow,
                created_at = DateTime.UtcNow
            };

            _context.Users.Add(user);
            await _context.SaveChangesAsync();

            return Ok(new { Message = "User registered successfully." });
        }

        [HttpPost("Login")]
        public async Task<ActionResult<User>> login(Login user)
        {
           var findUser = await _context.Users.SingleOrDefaultAsync(x => x.username == user.username || x.email == user.username);
           
            if (findUser == null || !BCrypt.Net.BCrypt.Verify(user.password, findUser.hashed_password))
            {
                return Unauthorized(new { message = "Invalid username or password" });
            }

           var token = GenerateJwtToken(findUser);
           return Ok(new {token});
        }

        private string GenerateJwtToken(User user)
        {
            var claims = new[]
            {
                new Claim(JwtRegisteredClaimNames.Jti, Guid.NewGuid().ToString()),
                new Claim("email", user.email),
                new Claim("name", user.username),
                new Claim(ClaimTypes.NameIdentifier, user.id.ToString())
            };

            var key = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(_configuration["JwtSettings:Key"] ?? Environment.GetEnvironmentVariable("Key")));
            var creds = new SigningCredentials(key, SecurityAlgorithms.HmacSha256);
            var token = new JwtSecurityToken(

            _configuration["JwtSettings:Issuer"] ?? Environment.GetEnvironmentVariable("Issuer"),
            _configuration["JwtSettings:Audience"] ?? Environment.GetEnvironmentVariable("Audience"),

            claims,

            expires: DateTime.Now.AddDays(30),

            signingCredentials: creds);

            return new JwtSecurityTokenHandler().WriteToken(token);
        }

        // DELETE: api/Users/5
        [HttpDelete("{id}")]
        public async Task<IActionResult> DeleteUser(int id)
        {
            var user = await _context.Users.FindAsync(id);
            if (user == null)
            {
                return NotFound();
            }

            _context.Users.Remove(user);
            await _context.SaveChangesAsync();

            return NoContent();
        }

        private bool UserExists(int id)
        {
            return _context.Users.Any(e => e.id == id);
        }
    }
}
