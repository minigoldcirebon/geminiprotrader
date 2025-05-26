# Test Webhook API
# This file contains sample webhook requests for testing

## 1. TradingView Webhook Test
curl -X POST http://localhost:8000/api/webhook/expert-signal \
  -H "Content-Type: application/json" \
  -H "X-Webhook-Source: tradingview" \
  -d '{
    "external_id": "TV_EURUSD_001",
    "source": "tradingview",
    "pair": "EURUSD",
    "signal_type": "BUY",
    "entry_price": 1.0850,
    "take_profit": 1.0920,
    "stop_loss": 1.0800,
    "confidence_level": 4,
    "risk_level": "medium",
    "timeframe": "1H",
    "analysis_reason": "Strong bullish divergence on RSI with breakout above resistance level",
    "expires_in_hours": 24
  }'

## 2. MT4 Webhook Test
curl -X POST http://localhost:8000/api/webhook/expert-signal \
  -H "Content-Type: application/json" \
  -H "X-Webhook-Source: mt4" \
  -d '{
    "external_id": "MT4_GBPUSD_002",
    "source": "mt4",
    "pair": "GBPUSD",
    "signal_type": "SELL",
    "entry_price": 1.2650,
    "take_profit": 1.2580,
    "stop_loss": 1.2700,
    "confidence_level": 5,
    "risk_level": "low",
    "timeframe": "4H",
    "analysis_reason": "Double top pattern confirmed with volume divergence",
    "expires_in_hours": 48
  }'

## 3. Custom Source Webhook Test
curl -X POST http://localhost:8000/api/webhook/expert-signal \
  -H "Content-Type: application/json" \
  -H "X-Webhook-Source: custom-bot" \
  -d '{
    "external_id": "BOT_USDJPY_003",
    "source": "custom-bot",
    "pair": "USDJPY",
    "signal_type": "HODL",
    "entry_price": 150.25,
    "take_profit": 152.00,
    "stop_loss": 148.50,
    "confidence_level": 3,
    "risk_level": "high",
    "timeframe": "1D",
    "analysis_reason": "Market consolidation phase, expect sideways movement before major breakout",
    "expires_in_hours": 72
  }'

## 4. Close Signal Test
curl -X POST http://localhost:8000/api/webhook/update-signal-result \
  -H "Content-Type: application/json" \
  -d '{
    "external_id": "TV_EURUSD_001",
    "source": "tradingview",
    "close_price": 1.0890,
    "result": "profit"
  }'
