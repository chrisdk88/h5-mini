import { localApiUrl } from "../../localApiUrl";

//------------------------ Scores ------------------------//
export const getScoresDailyURL = localApiUrl + "Scores/hasPlayedDailyWordle/{userId}";

export const getScoresURL = localApiUrl + "Scores/usersScoreSummary/{userId}";