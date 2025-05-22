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
        public int sender_id { get; set; } 
        public string senderUsername { get; set; } 
    }
    public class FriendDto
    {
        public int friendship_id { get; set; }
        public int user_id { get; set; }
        public string username { get; set; }
    }
}
