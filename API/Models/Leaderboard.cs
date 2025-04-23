namespace API.Models
{
    public class Leaderboard : Common
    {
        int user_id { get; set; }
        int score { get; set; }
        string game {  get; set; }
        DateOnly DateOnly { get; set; }
    }
}
