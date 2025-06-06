﻿namespace API.Models
{
    public class WordleSession : Common
    {
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public int word_id { get; set; }
        public int winner_id { get; set; }
        public DateOnly DateOnly { get; set; }

        // Navigation property (optional, used in EF)
        public WordleWords word { get; set; }
    }

    public class PostWordSession
    {
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public int word_id { get; set; }
        public int winner_id { get; set; }
        public DateOnly DateOnly { get; set; }
    }
}
