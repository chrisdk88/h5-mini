import { externalApiUrl } from "../../../externalApiUrl";

//------------------------ WordlSessions ------------------------//
export const getWordleSessionURL = externalApiUrl + "WordleSessions/getAllWordleSessions";

export const getWordleSessionsIdURL = externalApiUrl + "WordleSessions/getWordleSession/{Id}";

//------------------------ WordleWords ------------------------//
export const getRandomWordsURL = externalApiUrl + "WordleWords";

export const getRandomWordURL = externalApiUrl + "WordleWords/getRandomWord";

export const getRandomDailyWordURL = externalApiUrl + "WordleWords/getRandomDailyWord";

export const getWordFromCategoryURL = externalApiUrl + "WordleWords/getWordFromCategoryId/{categoryId}";