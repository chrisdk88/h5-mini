//------------------------ Base API URL ------------------------//
export const baseApiUrl = "http://localhost:5014/api/";

//------------------------ WordlSessions ------------------------//
export const getWordleSessionURL = baseApiUrl + "WordleSessions/getAllWordleSessions";

export const getWordleSessionsIdURL = baseApiUrl + "WordleSessions/getWordleSession/{Id}";

//------------------------ WordleWords ------------------------//
export const getRandomWordsURL = baseApiUrl + "WordleWords";

export const getRandomWordURL = baseApiUrl + "WordleWords/getRandomWord";

export const getRandomDailyWordURL = baseApiUrl + "WordleWords/getRandomDailyWord";

export const getWordFromCategoryURL = baseApiUrl + "WordleWords/getWordFromCategoryId/{categoryId}";