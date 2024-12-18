import axios, {AxiosResponse} from "axios";

export async function fetchData(url: string) {
	try {
		return await axios.get(url);
	} catch (e) {
		console.error("Failed to fetch data");
	}
}

export async function postData(url: string, data: any) {
	try {
		return await axios.post(url, data);
	} catch (e) {
		console.error("Posting data failed. ", e);
	}
}

export async function patchData(url: string, data: any) {
	try {
		await axios.patch(url, data);
	} catch (e) {
		console.error("Patching data failed. ", e);
	}
}

export async function deleteData(url: string) {
	try {
		return await axios.delete(url);
	} catch (e) {
		console.error("Deleting data failed. ", e)
	}
}

/**
 * Data fetching helper function for react query (should replace fetchData function in the future)
 * @param url
 */
export function fetchDataReactQuery(url: string) {
	return axios.get(url).then((response) => response.data);
}

/**
 * Data posting helper function for react query (should replace postData function in the future)
 * @param url
 * @param data
 */
export function postDataReactQuery(url: string, data: object) {
	return axios.post(url, data);
}

/**
 * Data patching helper function for react query (should replace patchData function in the future)
 * @param url
 * @param data
 */
export function patchDataReactQuery(url: string, data: object) {
	return axios.patch(url, data);
}

/**
 * Data deleting helper function for react query (should replace deleteData function in the future)
 * @param url
 */
export function deleteDataReactQuery(url: string) {
	return axios.delete(url);
}