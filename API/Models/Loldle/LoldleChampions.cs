using API.Models.Common;

namespace API.Models.Loldle
{
    public class LoldleChampions : CommonBase
    {
        public string name {  get; set; }
        public string position { get; set; }
        public string species { get; set; }
        public string resource { get; set; }
        public string rangeType { get; set; }
        public string region { get; set; }
        public string releaseDate { get; set; }

    }
}
