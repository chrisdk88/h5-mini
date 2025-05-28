import { baseApiUrl } from "../baseApiUrl";

//------------------------ Scores ------------------------//
export const getScoresDailyURL = baseApiUrl + "Scores/hasPlayedDailyWordle/{userId}";

export const getScoresURL = baseApiUrl + "Scores/usersScoreSummary/{userId}";