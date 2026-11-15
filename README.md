# AXD Media Website

Custom WordPress theme and support-desk plugin for [axdmedia.com](https://axdmedia.com).

| Folder | What it is |
|---|---|
| `axd-media/` | **AXD Media theme**: a lightweight block theme. No page builder; every section is editable in the WordPress editor. |
| `axd-support-desk/` | **AXD Support Desk plugin**: support tickets with case numbers, client email updates, and contact-form inquiries. |

Requirements: WordPress 6.6+ (built and tested on 7.1), PHP 8.1+ (8.3 recommended).

---

## Installing

1. **Back up the site first** (files and database).
2. **Plugins → Add New → Upload Plugin**: upload `axd-support-desk.zip`, then activate it.
3. **Appearance → Themes → Add New → Upload Theme**: upload `axd-media.zip`, then activate it.
   On activation the theme sets the AXD logo and creates the default project types and industries.
4. Create the pages (choose one):
   - **With WP-CLI (recommended):** `wp axd setup --dry-run` to preview, then `wp axd setup`.
     Existing pages are never changed unless you add `--overwrite`.
   - **Manually:** create a page, then in the editor open the block inserter, go to **Patterns → AXD: Full pages**, and insert the matching page (for example "Page: Home").
5. **Settings → Reading**: set the front page to "Home" (the CLI command does this for you).
6. **Support Desk → Settings**: confirm the notification email (info@axdmedia.com) and add your Client IDs.

### Page addresses

These match the current site wherever a page already exists, so existing links and search rankings carry over:

`/` · `/about-us/` · `/our-work/` · `/nonprofits/` · `/healthcare/` · `/legal/` · `/data-analytics-dashboards/` · `/custom-applications-ai/` · `/website-development/` · `/it-support-consulting/` · `/contact/` · `/support/`

The old `/services/` and `/project/` pages should be redirected (for example `/services/` → `/#solutions` and `/project/` → `/our-work/`) when the site goes live.

---

## Editing content

- **Pages:** open any page and click on text, images or buttons to change them. Sections are normal blocks.
- **Highlighted words in headings:** select the text, open the toolbar's ▾ menu and choose **Gradient text** (light backgrounds) or **Accent text (on navy)**.
- **Button styles:** select a button and choose a style in the sidebar: Fill (gradient), Outline, White, Support (blue) or Glass.
- **Header, footer and menus:** **Appearance → Editor → Patterns → Header / Footer**. Menu items keep their dropdown icons through these CSS classes (Block → Advanced → Additional CSS class):
  `axd-ico-data`, `axd-ico-apps`, `axd-ico-web`, `axd-ico-it`, `axd-ico-nonprofits`, `axd-ico-healthcare`, `axd-ico-legal`, `axd-ico-projects`.
- **Simple text pages** (Privacy Policy, Accessibility): use the **"Page with Title"** template in the page sidebar.

### Projects (Our Work)

**Projects → Add New**:
1. Title, **Excerpt** (the short card description) and **Featured image** (the card image).
2. Choose a **Project Type** (drives the filter buttons) and an **Industry**.
3. The body starts with a Challenge / Approach / Delivered / Results layout; replace the bracketed text.

Projects appear automatically on the Our Work page. Filter buttons appear only for types that have projects.

---

## Support Desk

**Clients** submit the form on `/support/`. They immediately see a case number such as `AXD-261115-0001` and receive a confirmation email. You receive an alert at the notification address.

**You** work cases under **Support Desk → Tickets**:
- **Reply to client (emailed):** the reply is emailed to the client and saved in the case history.
- **Internal note (private):** saved in the history only, never emailed.
- **Status:** New → In Progress → Waiting on Client → Resolved → Closed. Changing it emails the client (you can untick this).
- The red badge on the menu shows how many tickets are **New**.

**Contact form** messages from `/contact/` are saved under **Support Desk → Contact Inquiries** and emailed to you.

**Spam protection:** security token, hidden honeypot field, minimum fill time, and a per-visitor rate limit. No captcha needed.

**Email delivery:** install an SMTP plugin (for example WP Mail SMTP) connected to info@axdmedia.com in Microsoft 365 or Google Workspace. Without it, Bluehost's default mail may land in spam.

Client replies to emails arrive in your inbox; add them to the ticket with an internal note or reply. Automatically threading email replies into tickets is a possible later upgrade.

---

## Development

- `axd-media/inc/pattern-helpers.php` builds the page patterns from shared section functions, so all pages stay consistent.
- `axd-media/assets/css/theme.css` holds the styles; colors and fonts are in `theme.json`.
- Fonts (Inter, Plus Jakarta Sans) are self-hosted, so the site makes no Google Fonts requests.
- Front-end JavaScript is one small file (the Our Work filters).

Build the upload zips:

```bash
./build.sh
```
