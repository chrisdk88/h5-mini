﻿using System.Configuration;

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

        [HttpPut("Edit/{id}")]
        public async Task<IActionResult> PutUser(int id, User user)
        {

            return NoContent();
        }

        [HttpPost("Signup")]
        public async Task<ActionResult<User>> signup(Signup signup)
        {
            // Regex 
            // Only letters and numbers (5-15 chars)
            Regex validateUsername = new(@"^[a-zA-Z0-9]{5,15}$");
            // Standard email format
            Regex validateEmail = new(@"^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$");
            // Strong password
            Regex validatePassword = new(@"^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$");

            // Dictionary to store validation errors
            var errors = new Dictionary<string, string>();

            // Validate username format
            if (!validateUsername.IsMatch(signup.username))
            {
                errors["Username"] = "Username must be 5-15 characters long and contain only letters and numbers.";
            }

            // Check if username already exists
            if (_context.Users.Any(x => x.username == signup.username))
            {
                errors["Username"] = "Username is already taken.";
            }

            // Validate email format
            if (!validateEmail.IsMatch(signup.email))
            {
                errors["Email"] = "Invalid email format.";
            }

            // Check if email already exists
            if (_context.Users.Any(x => x.email == signup.email))
            {
                errors["Email"] = "Email is already registered.";
            }

            // Validate password strength
            if (!validatePassword.IsMatch(signup.password))
            {
                errors["Password"] = "Password must be at least 8 characters long, contain at least one letter, one number, and one special character.";
            }

            // If there are validation errors, return BadRequest (400) with error details
            if (errors.Count > 0)
            {
                return BadRequest(new { Errors = errors });
            }

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
