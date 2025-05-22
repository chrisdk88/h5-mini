using API.Models.Common;
using API.Models.Leaderboard;
using API.Models.Loldle;
using API.Models.Wordle;
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
        public DbSet<LoldleChampions> LolChampions { get; set; }
        public DbSet<GamedleSession> LolSessions { get; set; }
        public DbSet<WordleSession> WordleSessions { get; set; }
        public DbSet<WordleWords> WordleWords { get; set; }
        public DbSet<Score> Score { get; set; } = default!;
    }
}
