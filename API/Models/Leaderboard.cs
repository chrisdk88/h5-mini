namespace API.Models
{
    public class Leaderboard : Common
    {
        public int user_id { get; set; }
        public int score { get; set; }
        public string game {  get; set; }
        public DateOnly DateOnly { get; set; }
    }
}
