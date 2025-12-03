# Cloudflare + WordPress Spam Protection Guide

Since you have Cloudflare, you can add a powerful "outer shield" to your site. While the PHP code I wrote protects the *inside* of your house, Cloudflare protects the *front yard*.

Here are the 3 most important configurations to enable in your Cloudflare Dashboard:

## 1. Enable "Bot Fight Mode" (Free)
This uses machine learning to challenge known bots before they even see your site.

1. Go to **Security** > **Bots**
2. Toggle **Bot Fight Mode** to **On**
   * *Note: If you have the Pro plan, use "Super Bot Fight Mode" instead.*

## 2. Create a "Comment Challenge" Rule (Most Important)
This is a specific rule to force a browser check on anyone trying to post a comment. This kills 99% of automated scripts because they can't run JavaScript.

1. Go to **Security** > **WAF** > **Custom Rules**
2. Click **Create Rule**
3. **Rule Name**: `Protect Comments`
4. **Field**: `URI Path`
5. **Operator**: `contains`
6. **Value**: `/wp-comments-post.php`
7. **Choose Action**: `Managed Challenge` (or `Interactive Challenge`)
8. Click **Deploy**

**Why this works**: When a bot tries to POST data to the comment file, Cloudflare stops them and asks "Are you human?". Real humans won't even notice it (it happens in the background), but bots will fail.

## 3. Browser Integrity Check
Ensure this is on to block visitors with fake or missing user agents.

1. Go to **Security** > **Settings**
2. Ensure **Browser Integrity Check** is **On**

## How They Work Together

| Layer | Tool | What it Does |
|-------|------|--------------|
| **Layer 1 (Network)** | Cloudflare WAF | Blocks DDOS, known bad IPs, and dumb bots |
| **Layer 2 (Browser)** | Cloudflare Challenge | Forces visitors to prove they use a real browser |
| **Layer 3 (App)** | **My PHP Code** | Checks content, speed, and behavior (Honeypot, Math, Keywords) |

## Troubleshooting

If you enable these rules and legitimate users complain about being blocked:
1. Change the action in Rule #2 from `Interactive Challenge` to `Managed Challenge` (less intrusive).
2. Check the **Security** > **Events** log in Cloudflare to see exactly why they were blocked.

## Verification
The code update I just made to `inc/comment-spam-protection.php` ensures that even behind Cloudflare, we can see the visitor's *real* IP address. This is critical for the "Rate Limiting" feature to work correctly.
