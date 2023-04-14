const urlInputElem = document.getElementById("url-input");
const shortenBtnElem = document.getElementById("shorten-btn");
const urlOutputContainerElem = document.getElementById("url-output-container");
const shortenedUrlElem = document.getElementById("shortend-url");
const copyBtnElem = document.getElementById("copy-btn");

const API_URL = "/api";

function getSiteDomain() {
  return window.location.href.split("/")[2];
}

async function fetchShortenedUrl(longUrl) {
  const formData = new FormData();
  formData.append("url", longUrl);
  const res = await fetch(API_URL, {
    method: "POST",
    body: formData
  });
  if (!res.ok) {
    if (res.status >= 400 && res.status <= 499) {
      return false;
    }
    throw new Error("Server error");
  }
  const json = await res.json();
  return json["url"];
}


function clearUrl() {
  urlOutputContainerElem.classList.remove("hidden");
  urlInputElem.removeAttribute("aria-invalid");
  shortenedUrlElem.classList.remove("invalid");
  shortenedUrlElem.href = shortenedUrlElem.innerText = "";
}

function displayShortenedUrl(shortenedUrl) {
  shortenedUrlElem.innerText = `${getSiteDomain()}/${shortenedUrl}`;
  shortenedUrlElem.href = `/${shortenedUrl}`;
  copyBtnElem.removeAttribute("disabled");
}

function displayInvalidUrl() {
  urlInputElem.setAttribute("aria-invalid", "true");
  copyBtnElem.setAttribute("disabled", "true");
  shortenedUrlElem.href = "";
  shortenedUrlElem.innerText = "Invalid URL";
  shortenedUrlElem.classList.add("invalid");
}

function displayLoading(isVisible) {
  shortenedUrlElem.setAttribute("aria-busy", String(isVisible));
  if (isVisible) {
    copyBtnElem.setAttribute("disabled", "true");
  }
}

function handleShortenUrl() {
  clearUrl();
  displayLoading(true);
  setTimeout(async () => {
    const shortenedUrl = await fetchShortenedUrl(urlInputElem.value);
    if (shortenedUrl === false) {
      displayInvalidUrl();
    } else {
      displayShortenedUrl(shortenedUrl);
    }
    displayLoading(false);
  }, 100);
}


urlInputElem.addEventListener("keydown", (e) =>
  e.code === "Enter" && shortenBtnElem.click()
);

shortenBtnElem.addEventListener("click", () => handleShortenUrl());

copyBtnElem.addEventListener("click", () => {
  if (!window.isSecureContext) {
    return;
  }
  navigator.clipboard.writeText(shortenedUrlElem.innerText);
  copyBtnElem.setAttribute("data-tooltip", "Copied");
  setTimeout(() => {
    copyBtnElem.removeAttribute("data-tooltip");
  }, 1500);
});
