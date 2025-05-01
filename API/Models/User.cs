namespace API.Models
{
    public class User : Common
    {
        public string username { get; set; }
        public string email { get; set; }
        public string hashed_password { get; set; }
        public string role { set; get; } = "User";
        public bool banned { get; set; } = false;
        public int level { get; set; } = 0;

    }

    public class Signup
    {
        public string username { get; set; }
        public string email { set; get; }
        public string password { set; get; }
    }

    public class Edit
    {
        public string? email { get; set; }
        public string? username { get; set; }
        public string? password { get; set; }
    }
    public class increaseLevel
    {
        public int level { get; set; }
    }
    public class Login
    {
        public string username { get; set; }
        public string password {  get; set; }
    }

    public class EditRole
    {
        public string role { get; set; }
    }

    public class BanUser
    {
        public bool banned { get; set; } = true;
    }
}
