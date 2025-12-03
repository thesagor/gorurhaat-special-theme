# Comment Spam Protection System

## Overview
This theme includes a comprehensive, multi-layered spam protection system that prevents automated bots and spammers from posting unwanted comments on your WordPress site.

## Features

### 1. **Honeypot Field** 🍯
- **What it does**: Adds a hidden field that is invisible to human users but visible to bots
- **How it works**: Bots typically fill out all form fields automatically. If this hidden field is filled, the comment is rejected
- **User Impact**: None - completely invisible to legitimate users

### 2. **Time-Based Validation** ⏱️
- **What it does**: Ensures comments aren't submitted too quickly after the page loads
- **How it works**: 
  - Minimum 3 seconds must pass between form load and submission
  - Maximum 1 hour (prevents stale form submissions)
- **User Impact**: Minimal - prevents accidental instant submissions
- **Blocks**: Automated bots that submit forms instantly

### 3. **Math CAPTCHA** ➕
- **What it does**: Requires users to solve a simple math problem (e.g., "What is 5 + 3?")
- **How it works**: Generates random addition problems with numbers 1-10
- **User Impact**: Low - simple math that takes 2-3 seconds
- **Blocks**: Automated bots that can't solve basic math

### 4. **Spam Keyword Filtering** 🚫
- **What it does**: Blocks comments containing common spam keywords
- **Blocked keywords include**:
  - Pharmaceutical spam: viagra, cialis, diet pills
  - Financial spam: casino, poker, lottery, forex, bitcoin
  - Marketing spam: SEO service, backlinks, essay writing
  - Scam phrases: make money fast, work from home, free money
- **User Impact**: None for legitimate users
- **Customizable**: You can add/remove keywords in the code

### 5. **Link Limit** 🔗
- **What it does**: Limits the number of links allowed in a comment
- **Default**: Maximum 2 links per comment
- **User Impact**: Minimal - legitimate comments rarely need many links
- **Blocks**: Spam comments that typically contain multiple promotional links

### 6. **Rate Limiting** 🛑
- **What it does**: Limits how many comments one IP address can post in a time window
- **Default**: Maximum 3 comments per 5 minutes per IP address
- **User Impact**: Minimal for normal users
- **Blocks**: Spam bots posting multiple comments rapidly

### 7. **Duplicate Comment Detection** 🔄
- **What it does**: Prevents posting the same or very similar comment multiple times
- **How it works**: Checks for 80%+ similarity with recent comments from the same email
- **Time window**: 1 hour
- **User Impact**: Prevents accidental double-posting

### 8. **Minimum Comment Length** 📏
- **What it does**: Requires comments to be at least 10 characters long
- **User Impact**: Minimal - encourages meaningful comments
- **Blocks**: Low-effort spam like "nice post" or "great!"

### 9. **All-Caps Detection** 🔠
- **What it does**: Blocks comments written entirely in capital letters
- **Threshold**: Comments over 20 characters
- **User Impact**: Minimal - discourages shouting
- **Blocks**: Common spam pattern

## Configuration

### Adjusting Settings

You can customize the spam protection by editing `/inc/comment-spam-protection.php`:

```php
// Maximum links allowed
private $max_links = 2; // Change to your preference

// Minimum time before submission (seconds)
private $min_time = 3; // Increase for stricter protection

// Rate limiting
private $max_comments_per_ip = 3; // Comments allowed
private $rate_limit_window = 300; // Time window (5 minutes)
```

### Adding Custom Spam Keywords

Edit the `$spam_keywords` array in the class:

```php
private $spam_keywords = array(
    'viagra', 'cialis', 'casino',
    // Add your custom keywords here
    'your-keyword',
    'another-keyword'
);
```

### Disabling Specific Features

Comment out the corresponding hook in the `__construct()` method:

```php
// To disable math CAPTCHA, comment out these lines:
// add_action( 'comment_form_after_fields', array( $this, 'add_math_captcha' ) );
// add_action( 'comment_form_logged_in_after', array( $this, 'add_math_captcha' ) );
```

## Admin Exemption

**Important**: All spam protection checks are automatically bypassed for users with `moderate_comments` capability (typically Administrators and Editors).

This means:
- Admins can post comments instantly
- No CAPTCHA required for admins
- No rate limiting for admins
- No keyword filtering for admins

## Error Messages

The system provides clear, user-friendly error messages:

| Error | Message | Cause |
|-------|---------|-------|
| Honeypot triggered | "Spam detected" | Bot filled hidden field |
| Too fast | "You are posting too quickly" | Submitted < 3 seconds |
| Session expired | "Your session has expired" | Submitted > 1 hour |
| Wrong CAPTCHA | "Incorrect answer to the math question" | Failed math challenge |
| Spam keywords | "Contains prohibited content" | Used blacklisted words |
| Too many links | "Too many links (maximum 2 allowed)" | Exceeded link limit |
| Rate limited | "Posting comments too frequently" | Too many comments |
| Duplicate | "Duplicate comment detected" | Same comment posted twice |
| Too short | "Comment is too short" | Less than 10 characters |
| All caps | "Do not write in all capital letters" | ENTIRE COMMENT IN CAPS |

## Testing the Protection

### Test as a Regular User:
1. Try posting a comment immediately after page load (should fail)
2. Wait 3+ seconds and post a valid comment (should succeed)
3. Try posting with wrong math answer (should fail)
4. Try posting with spam keywords like "viagra" (should fail)
5. Try posting with 3+ links (should fail)

### Test as Admin:
1. Log in as administrator
2. All protections should be bypassed
3. You can post comments instantly without CAPTCHA

## Performance Impact

- **Minimal**: All checks are lightweight PHP operations
- **No external API calls**: Everything runs on your server
- **No JavaScript required**: Works even with JS disabled
- **Session usage**: Uses PHP sessions for CAPTCHA (standard WordPress practice)

## Compatibility

- ✅ WordPress 5.0+
- ✅ All modern themes
- ✅ WooCommerce product reviews
- ✅ Standard WordPress comments
- ✅ Works with comment plugins (most)
- ✅ Multisite compatible

## Troubleshooting

### Issue: Legitimate users can't post comments

**Solution**: Check if your settings are too strict:
- Reduce `$min_time` to 2 seconds
- Increase `$max_links` to 3-4
- Increase `$max_comments_per_ip` to 5

### Issue: Math CAPTCHA not showing

**Solution**: 
- Check if your theme supports `comment_form_after_fields` hook
- Verify PHP sessions are enabled on your server

### Issue: Still getting spam

**Solution**:
- Add more spam keywords to the blacklist
- Reduce `$max_links` to 1
- Reduce `$max_comments_per_ip` to 2
- Consider adding a WordPress anti-spam plugin for additional protection

### Issue: Error "Session expired"

**Solution**: This is normal for users who leave the comment form open for over 1 hour. They just need to refresh and try again.

## Advanced: Combining with Other Plugins

This spam protection works alongside:
- **Akismet**: Use both for maximum protection
- **Jetpack Comments**: Compatible
- **Disqus**: Not needed (Disqus has its own protection)
- **reCAPTCHA plugins**: Can use both, but may be redundant

## Security Notes

- All user input is sanitized and validated
- Uses WordPress nonces and tokens
- IP addresses are stored securely
- No sensitive data is logged
- GDPR compliant (only stores comment data WordPress already stores)

## Customization for WooCommerce Reviews

The protection automatically works for WooCommerce product reviews since they use the WordPress comment system.

To adjust settings specifically for product reviews, you can add conditional logic:

```php
// In validate_comment method, add:
if ( get_post_type( $commentdata['comment_post_ID'] ) === 'product' ) {
    // Custom rules for product reviews
    $this->max_links = 1; // Stricter for products
}
```

## Future Enhancements

Potential additions you could make:
- [ ] IP blacklist/whitelist
- [ ] Country-based blocking (using GeoIP)
- [ ] Machine learning spam detection
- [ ] Integration with external spam databases
- [ ] Admin dashboard for spam statistics
- [ ] Email notifications for blocked spam attempts

## Support

For issues or questions:
1. Check the error message - they're designed to be helpful
2. Review the configuration settings
3. Test with admin account to verify it's not a permission issue
4. Check PHP error logs for any system errors

## Credits

Built with ❤️ for the Gorurhaat Special Theme
- Multi-layered approach inspired by industry best practices
- User-friendly design prioritizing legitimate user experience
- Zero external dependencies for maximum reliability

---

**Last Updated**: December 2025
**Version**: 1.0.0
