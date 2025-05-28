import { externalApiUrl } from "../../externalApiUrl";

//------------------------ Scores ------------------------//
export const getScoresDailyURL = externalApiUrl + "Scores/hasPlayedDailyWordle/{userId}";

export const getScoresURL = externalApiUrl + "Scores/usersScoreSummary/{userId}";