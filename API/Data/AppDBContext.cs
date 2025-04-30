using API.Models;
using Microsoft.EntityFrameworkCore;

namespace API.Data
{
    public class AppDBContext : DbContext
    {
        public AppDBContext(DbContextOptions<AppDBContext> options) : base(options)
        {
        }

        public DbSet<User> Users { get; set; }
        public DbSet<Category> Categories { get; set; }
        public DbSet<Friends> Friends { get; set; }
        public DbSet<GameInvites> GameInvites { get; set; }
        public DbSet<Leaderboard> leaderboards { get; set; }
        public DbSet<LolHeroes> LolHeroes { get; set; }
        public DbSet<LolSession> LolSessions { get; set; }
        public DbSet<WordleSession> WordleSessions { get; set; }
        public DbSet<WordleWords> WordleWords { get; set; }
        public DbSet<Score> Score { get; set; } = default!;
    }
}
