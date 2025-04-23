namespace API.Models
{
    public class GameInvites : Common
    {
        int player1_id {  get; set; }
        public int player2_id { get; set;}
        string status { get; set; }
        string game_type {  get; set; }
        int game_session_id {  get; set; }
    }
}
