let isLoading = false;
const loading = `
                    <td>
                      <svg height='32px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                      </svg>
                    </td>
                  `;

export function showLoading(body) {
  const trLoading = document.createElement("tr");
  trLoading.innerHTML = loading;
  trLoading.classList.add("loading");
  body.appendChild(trLoading);
}
