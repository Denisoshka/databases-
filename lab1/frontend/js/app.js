var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import { ApiUrl } from "./consts.js";
function fetchUsers() {
    return __awaiter(this, void 0, void 0, function* () {
        const response = yield fetch(`${ApiUrl}/users.php`);
        return yield response.json();
    });
}
function renderUsers(users) {
    const userList = document.getElementById("user-list");
    userList.innerHTML = "";
    users.forEach(user => {
        const userItem = document.createElement("li");
        userItem.textContent = `${user.name} (${user.email})`;
        userList.appendChild(userItem);
    });
}
fetchUsers().then(renderUsers);
//# sourceMappingURL=app.js.map