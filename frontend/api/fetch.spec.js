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
    try{
        const response = await axios(...arguments);
        return ({ json() { return new Promise(res => res(response.data)); } });
        
    } catch(e){
        return `Error: ${e}`
    }
}

vi.stubGlobal("fetch", fetch)
vi.stubGlobal("localStorage", new Proxy(mockLocalStorage, handler))

const storage = {
    token_api: '{"token": "63|r56GABkFbJ6UtWFjaFqT02zONwW07iZ3pRYHbXJl"}',
    getItem(key){
        return storage[key];
    },
    setItem(key, value){
        storage[key] = value;
    },
    removeItem(key){
        console.log("Delete... " + key)
        delete storage[key];
    }
}

vi.stubGlobal("localStorage", new Proxy(storage, {
    get(target, prop){
        console.log(prop)
        return target[prop]
    }
}))

describe("Test Client-side fetching 'Fetch.js'", () => {
    let email = "ikhttiar@gmail.com";
    let password = "hello world";

    it("Test localStorage token checking", () => {
        let app = new App();
        
        return app.user_session.then(res => console.log(res))
    })

    it.skip('Test login with ("ikhttiar@gmail.com", "hello world")', () => { 
        let session = new App(email, password);
        return session.user_session.then(res => console.log(res));
     })
})