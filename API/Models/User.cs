namespace API.Models
{
    public class User : Common
    {
        public string name {  get; set; }
        public string email { get; set; }
        public string hashed_password { get; set; }
        public string role {  get; set; }
    }
}
