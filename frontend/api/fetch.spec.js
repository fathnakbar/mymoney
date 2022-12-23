import { it, describe, vi } from "vitest"
import { App } from "./Fetch"
import axios from "axios"

const mockLocalStorage = {};

let handler = {
    get(target, prop, receiver){
        if (prop === "setItem") {
            return function(key, value) {
                target[key] = value;
            }
        }

        if (prop === "getItem") {
            return function (key) {
                return target[key]
            }
        }

        return Reflect.get(...arguments);
    }
}

async function fetch() {
    const response = await axios(...arguments);
    return ({ json() { return new Promise(res => res(response.data)); } });
}

vi.stubGlobal("fetch", fetch)
vi.stubGlobal("localStorage", new Proxy(mockLocalStorage, handler))

describe("Test Client-side fetching 'Fetch.js'", () => {
    let session;
    let email = "ikhttiar@gmail.com";
    let password = "hello world";

    it('Test login with ("ikhttiar@gmail.com", "hello world")', () => { 
        session = new App(email, password);
        return session.user_session.then(res => console.log(res));
     })
})