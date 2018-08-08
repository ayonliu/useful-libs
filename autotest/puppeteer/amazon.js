const puppeteer = require('puppeteer');

(async () => {
    const brwoser = await puppeteer.launch({headless: false});
    // const brwoser = await puppeteer.launch({devtools: true});
    const page = await brwoser.newPage();
    login_url = 'https://affiliate-program.amazon.com/';
    await page.setViewport({width:1360, height:768});
    await page.goto(login_url, {
        waitUntil: "networkidle2"
    });
    await page.waitFor('#a-autoid-0-announce');
    await page.click("#a-autoid-0-announce", {
        delay: 500
    });
    // await page.waitForNavigation({waitUntil: 'load', timeout: 0});
    await page.waitFor('#ap_email');
    await page.waitFor('#ap_password');
    await page.waitFor("#signInSubmit")
    // your login email here
    await page.type("#ap_email", '', {delay: 100});
    // you password here
    await page.type("#ap_password", '', {delay: 100});
    await page.click("#signInSubmit", {
        delay: 300
    });
    await page.waitForNavigation({waitUntil: 'load', timeout: 0});
    console.info(page.title());
    console.info(page.url());

    await page.screenshot({path: 'amazon.png'});

    await brwoser.close();
})();
