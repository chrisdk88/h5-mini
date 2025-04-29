namespace API.Models
{
    public class Pts : Common
    {
        public int UserId { get; set; }
        public int GamemodeId { get; set; }
        public int Score { get; set; }
        public bool IsMultiplayer { get; set; }
        public int? GameSessionId { get; set; } //null if single player
    }
    public class postScore 
    {
        public int UserId { get; set; }
        public int GamemodeId { get; set; }
        public int Score { get; set; }
        public bool IsMultiplayer { get; set; }
        public int? GameSessionId { get; set; } //null if single player
    }
}
