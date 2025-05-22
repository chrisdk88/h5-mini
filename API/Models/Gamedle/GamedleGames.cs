using API.Models.Common;

namespace API.Models.Gamedle
{
    public class GamedleGames : CommonBase
    {
        public string name { get; set; }
        public string genre { get; set; }
        public string platform { get; set; }
        public string releaseDate { get; set; }
        public string developer { get; set; }
        public string publisher { get; set; }
    }
}
