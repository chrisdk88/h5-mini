//------------------------ Base API URL ------------------------//
export const baseApiUrl = "http://localhost:5014/api/";

//------------------------ GET ------------------------//

//------------------------ Categories ------------------------//
export const getCategoriesURL = baseApiUrl + "Categories";

export const getCategoriesIdURL = baseApiUrl + "Categories/{id}";

//------------------------ Friends ------------------------//
export const getFriendsURL =  baseApiUrl + "Friends";

export const getUserFriendsURL =  baseApiUrl + "Friends/getUsersFriends/{userId}";

export const getFriendsRequestsURL =  baseApiUrl + "Friends/requests/{userId}";

export const getFriendsSearchURL =  baseApiUrl + "Friends/Search";

//------------------------ GameInvites ------------------------//
export const getGameInvitesURL = baseApiUrl + "GameInvites";

export const getGameInvitesGetGameInviteURL = baseApiUrl + "GameInvites/getGameInvite/{id}";

//------------------------ Leaderboards ------------------------//
export const getLeaderboardURL = baseApiUrl + "Leaderboards";

//------------------------ LolChampions ------------------------//
export const getLolChampionsURL = baseApiUrl + "LolChampions";

export const getLolChampionsIdURL = baseApiUrl + "LolChampions/{id}";

//------------------------ LolSessions ------------------------//
export const getLolSessionsURL = baseApiUrl + "LolSessions";

export const getLolSessionsIdURL = baseApiUrl + "LolSessions/{id}";

//------------------------ Scores ------------------------//
export const getScoresDailyURL = baseApiUrl + "Scores/hasPlayedDailyWordle/{userId}";

export const getScoresURL = baseApiUrl + "Scores/usersScoreSummary/{userId}";

//------------------------ Users ------------------------//
export const getUsersURL = baseApiUrl + "Users";

export const getUsersIdURL = baseApiUrl + "Users/{userId}";

export const getUsersExpLvlURL = baseApiUrl + "Users/GetUsersExpAndLevel/{userId}";

export const getUsersAdminURL = baseApiUrl + "Users/AdminSearch";

//------------------------ WordlSessions ------------------------//
export const getWordleSessionURL = baseApiUrl + "WordleSessions/getAllWordleSessions";

export const getWordleSessionsIdURL = baseApiUrl + "WordleSessions/getWordleSession/{Id}";

//------------------------ WordleWords ------------------------//
export const getRandomWordsURL = baseApiUrl + "WordleWords";

export const getRandomWordURL = baseApiUrl + "WordleWords/getRandomWord";

export const getRandomDailyWordURL = baseApiUrl + "WordleWords/getRandomDailyWord";

export const getWordFromCategoryURL = baseApiUrl + "WordleWords/getWordFromCategoryId/{categoryId}";