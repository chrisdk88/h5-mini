namespace API.Models
{
    public class GameInvites : Common
    {
        public int player1_id {  get; set; }
        public int player2_id { get; set;}
        public string status { get; set; }
        public string game_type {  get; set; }
        public int game_session_id {  get; set; }
    }
}
