import { httpClientData } from './index';

export class ApiRouteGenerator {
    public static generatePath(resourcePath: String, withDebug: Boolean = false): String {
        let apiPath = `${httpClientData.apiUrl}/${resourcePath}`;

        if (withDebug) {
            apiPath += '?XDEBUG_SESSION_START=PHPSTORM';
        }

        return apiPath;
    }
}