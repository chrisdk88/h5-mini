//------------------------ Base API URL ------------------------//
export const baseApiUrl = "http://localhost:5014/api/";

//------------------------ Scores ------------------------//
export const getScoresDailyURL = baseApiUrl + "Scores/hasPlayedDailyWordle/{userId}";

export const getScoresURL = baseApiUrl + "Scores/usersScoreSummary/{userId}";