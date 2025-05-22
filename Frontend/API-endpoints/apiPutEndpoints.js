//------------------------ Base API URL ------------------------//
export const baseApiUrl = "http://localhost:5014/api/";

//------------------------ PUT ------------------------//

//------------------------ Categories ------------------------//
export const putCategoriesURL = baseApiUrl + "Categories/editCategory/{id}";

//------------------------ Friends ------------------------//
export const putFriendsURL = baseApiUrl + "Friends/acceptFriendRequest/{id}";

//------------------------ GameInvites ------------------------//
export const putGameInvitesURL = baseApiUrl + "GameInvites/editGameInvite/{id}";

//------------------------ Leaderboards ------------------------//
export const putLeaderboardURL = baseApiUrl + "";

//------------------------ LolChampions ------------------------//
export const putLolChampionsURL = baseApiUrl + "LolChampions/{id}";

//------------------------ LolSessions ------------------------//
export const putLolSessionsURL = baseApiUrl + "LolSessions/{id}";

//------------------------ Scores ------------------------//
export const putScoresURL = baseApiUrl + "Scores/editScore/{id}";

//------------------------ Users ------------------------//
export const putUsersBanURL = baseApiUrl + "Users/banUser/{userId}";

export const putUsersRoleURL = baseApiUrl + "Users/Role/{userId}";

export const putUsersincreaseExpURL = baseApiUrl + "Users/increaseExp/{userId}";

export const putUsersEditURL = baseApiUrl + "Users/Edit/{userId}";

//------------------------ WordlSessions ------------------------//
export const putRandomWordURL = baseApiUrl + "";

//------------------------ WordleWords ------------------------//
export const putRandomDailyWordURL = baseApiUrl + "";