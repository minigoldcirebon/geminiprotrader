# Webhook API Documentation

## Expert Signals Webhook Integration

This document describes the webhook endpoints available for integrating external trading signal sources with the Expert Signals system.

## Base URL
```
https://your-domain.com/api/webhook/
```

## Authentication
No API key required for webhook endpoints. Webhooks are secured through payload validation and source verification.

## Endpoints

### 1. Create Expert Signal
**POST** `/expert-signal`

Creates a new expert signal from an external source.

#### Request Headers
```
Content-Type: application/json
X-Webhook-Source: your-source-name (optional)
```

#### Request Body
```json
{
  "external_id": "unique-signal-id-123",
  "source": "tradingview",
  "pair": "BTCUSDT",
  "signal_type": "BUY",
  "entry_price": 45000.50,
  "take_profit": 47000.00,
  "stop_loss": 43500.00,
  "confidence_level": 4,
  "risk_level": "medium",
  "timeframe": "1H",
  "analysis_reason": "Strong bullish breakout with high volume",
  "expires_in_hours": 24,
  "chart_url": "https://example.com/chart-image.png",
  "priority": "high",
  "metadata": {
    "strategy_name": "Breakout Strategy",
    "backtest_winrate": 75.5
  }
}
```

#### Response
```json
{
  "success": true,
  "message": "Expert signal created successfully",
  "signal": {
    "id": 123,
    "external_id": "unique-signal-id-123",
    "pair": "BTCUSDT",
    "signal_type": "BUY",
    "entry_price": 45000.50,
    "status": "published",
    "published_at": "2025-05-26T10:30:00Z"
  }
}
```

### 2. Update Signal Result (Close Signal)
**POST** `/signal-result`

Updates a signal with closing price and profit/loss information.

#### Request Body
```json
{
  "external_id": "unique-signal-id-123",
  "source": "tradingview",
  "close_price": 46500.75,
  "result": "profit"
}
```

#### Response
```json
{
  "success": true,
  "message": "Signal result updated successfully",
  "signal": {
    "id": 123,
    "external_id": "unique-signal-id-123",
    "close_price": 46500.75,
    "profit_loss_percentage": 3.33,
    "signal_result": "profit",
    "closed_at": "2025-05-26T14:30:00Z"
  }
}
```

## Field Specifications

### Required Fields
- `external_id`: Unique identifier from your system
- `source`: Source system name (e.g., "tradingview", "mt4", "custom")
- `pair`: Trading pair (e.g., "BTCUSDT", "EURUSD")
- `signal_type`: "BUY", "SELL", or "HODL"
- `entry_price`: Entry price as number
- `analysis_reason`: Brief analysis explanation

### Optional Fields
- `take_profit`: Target profit price
- `stop_loss`: Stop loss price
- `confidence_level`: 1-5 (1=very low, 5=very high)
- `risk_level`: "low", "medium", "high"
- `timeframe`: Trading timeframe (e.g., "1H", "4H", "1D")
- `expires_in_hours`: Signal expiration in hours (default: 24)
- `chart_url`/`image_url`: URL to chart image
- `priority`: "low", "medium", "high"
- `metadata`: Additional data as JSON object

## Example Integrations

### TradingView Alert Webhook
```javascript
// TradingView Alert Message
{
  "external_id": "{{ticker}}_{{time}}",
  "source": "tradingview",
  "pair": "{{ticker}}",
  "signal_type": "{{strategy.order.action}}",
  "entry_price": {{close}},
  "take_profit": {{strategy.order.take_profit}},
  "stop_loss": {{strategy.order.stop_loss}},
  "confidence_level": 4,
  "risk_level": "medium",
  "timeframe": "{{interval}}",
  "analysis_reason": "TradingView Strategy Alert - {{strategy.order.comment}}",
  "expires_in_hours": 12
}
```

### MT4/MT5 Expert Advisor
```json
{
  "external_id": "{{magic_number}}_{{ticket}}",
  "source": "mt4",
  "pair": "{{symbol}}",
  "signal_type": "{{order_type}}",
  "entry_price": {{open_price}},
  "take_profit": {{take_profit}},
  "stop_loss": {{stop_loss}},
  "confidence_level": 5,
  "risk_level": "medium",
  "analysis_reason": "MT4 Expert Advisor Signal",
  "metadata": {
    "magic_number": {{magic_number}},
    "lot_size": {{lot_size}},
    "spread": {{spread}}
  }
}
```

### Custom Trading Bot
```json
{
  "external_id": "bot_signal_{{timestamp}}",
  "source": "custom_bot",
  "pair": "ETHUSDT",
  "signal_type": "BUY",
  "entry_price": 3245.50,
  "take_profit": 3400.00,
  "stop_loss": 3100.00,
  "confidence_level": 3,
  "risk_level": "high",
  "timeframe": "4H",
  "analysis_reason": "RSI oversold + MACD bullish crossover",
  "chart_url": "https://mybot.com/charts/eth_4h_analysis.png",
  "expires_in_hours": 48,
  "metadata": {
    "rsi_value": 25.4,
    "macd_signal": "bullish_cross",
    "volume_spike": true
  }
}
```

## Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "entry_price": ["The entry price field is required."],
    "signal_type": ["The signal type must be one of: BUY, SELL, HODL."]
  }
}
```

### Duplicate Signal (409)
```json
{
  "success": false,
  "message": "Signal with this external_id already exists",
  "signal_id": 123
}
```

### Signal Not Found (404)
```json
{
  "success": false,
  "message": "Signal not found"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Internal server error"
}
```

## Testing

### Test Signal Creation
```bash
curl -X POST https://your-domain.com/api/webhook/expert-signal \
  -H "Content-Type: application/json" \
  -d '{
    "external_id": "test_signal_001",
    "source": "test",
    "pair": "BTCUSDT",
    "signal_type": "BUY",
    "entry_price": 45000,
    "analysis_reason": "Test signal",
    "confidence_level": 3
  }'
```

### Test Signal Close
```bash
curl -X POST https://your-domain.com/api/webhook/signal-result \
  -H "Content-Type: application/json" \
  -d '{
    "external_id": "test_signal_001",
    "source": "test",
    "close_price": 46000
  }'
```

## Rate Limiting
- 100 requests per minute per IP
- 500 requests per hour per source

## Support
For webhook integration support, contact: support@geminipro.com
