import {ApiUrl} from "./consts.js"
// console.log(ApiUrl); // Вывод: 42

interface User {
  id: number;
  name: string;
  email: string;
}

async function fetchUsers(): Promise<User[]> {
  const response = await fetch(`${ApiUrl}/users.php`);
  if (response.ok){}
  return await response.json();
}

function renderUsers(users: User[]): void {
  const userList = document.getElementById("user-list");
  userList!.innerHTML = "";
  users.forEach(user => {
    const userItem = document.createElement("li");
    userItem.textContent = `${user.name} (${user.email})`;
    userList!.appendChild(userItem);
  });
}

fetchUsers().then(renderUsers);
