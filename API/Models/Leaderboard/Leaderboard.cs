using API.Models.Common;

namespace API.Models.Leaderboard
{
    public class Leaderboard : CommonBase
    {
        public int user_id { get; set; }
        public int score { get; set; }
        public string game { get; set; }
        public DateOnly DateOnly { get; set; }
    }
}
