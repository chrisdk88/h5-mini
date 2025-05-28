import { localApiUrl } from "../../../localApiUrl";

//------------------------ WordlSessions ------------------------//
export const getWordleSessionURL = localApiUrl + "WordleSessions/getAllWordleSessions";

export const getWordleSessionsIdURL = localApiUrl + "WordleSessions/getWordleSession/{Id}";

//------------------------ WordleWords ------------------------//
export const getRandomWordsURL = localApiUrl + "WordleWords";

export const getRandomWordURL = localApiUrl + "WordleWords/getRandomWord";

export const getRandomDailyWordURL = localApiUrl + "WordleWords/getRandomDailyWord";

export const getWordFromCategoryURL = localApiUrl + "WordleWords/getWordFromCategoryId/{categoryId}";