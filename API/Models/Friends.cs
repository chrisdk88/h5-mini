namespace API.Models
{
    public class Friends : Common
    {
        public int user1_id { get; set; }
        public int user2_id { get; set; }
        public bool status { get; set; }
    }

    public class AddFriend
    {
        public int user1_id { get; set; }
        public int user2_id { get; set; }
    }
    public class GetFriendRequestDto
    {
        public int SenderId { get; set; } 
        public string SenderUsername { get; set; } 
    }
    public class FriendDto
    {
        public int UserId { get; set; }
        public string Username { get; set; }
    }
}
