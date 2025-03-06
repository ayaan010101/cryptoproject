import React, { useEffect, useRef } from "react";

interface TradingChartProps {
  width?: number;
  height?: number;
  data?: number[];
  color?: string;
  backgroundColor?: string;
}

const TradingChart: React.FC<TradingChartProps> = ({
  width = 800,
  height = 300,
  data = [],
  color = "#3b82f6",
  backgroundColor = "transparent",
}) => {
  const canvasRef = useRef<HTMLCanvasElement>(null);

  // Generate random data if none provided
  const chartData = data.length > 0 ? data : generateRandomData(100);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;

    const ctx = canvas.getContext("2d");
    if (!ctx) return;

    // Clear canvas
    ctx.clearRect(0, 0, width, height);

    // Set background
    ctx.fillStyle = backgroundColor;
    ctx.fillRect(0, 0, width, height);

    // Draw chart
    drawChart(ctx, chartData, width, height, color);

    // Add grid lines
    drawGrid(ctx, width, height);

    // Add price indicators
    drawPriceIndicators(ctx, chartData, width, height);
  }, [chartData, width, height, color, backgroundColor]);

  return (
    <canvas
      ref={canvasRef}
      width={width}
      height={height}
      className="rounded-md"
    />
  );
};

// Generate random price data with a realistic pattern
function generateRandomData(points: number): number[] {
  const data: number[] = [];
  let value = 40000 + Math.random() * 5000; // Starting around 40-45k (like BTC)

  for (let i = 0; i < points; i++) {
    // Create more realistic movements
    const change = (Math.random() - 0.48) * 200; // Slightly biased upward
    value += change;

    // Ensure value stays positive and within a reasonable range
    value = Math.max(value, 35000);
    value = Math.min(value, 50000);

    data.push(value);
  }

  return data;
}

// Draw the main chart line
function drawChart(
  ctx: CanvasRenderingContext2D,
  data: number[],
  width: number,
  height: number,
  color: string,
) {
  const max = Math.max(...data) * 1.05; // Add 5% padding
  const min = Math.min(...data) * 0.95; // Subtract 5% padding
  const range = max - min;

  const xStep = width / (data.length - 1);

  // Draw the line
  ctx.beginPath();
  ctx.strokeStyle = color;
  ctx.lineWidth = 2;

  // Move to first point
  const initialY = height - ((data[0] - min) / range) * height;
  ctx.moveTo(0, initialY);

  // Draw lines to each point
  for (let i = 1; i < data.length; i++) {
    const x = i * xStep;
    const y = height - ((data[i] - min) / range) * height;
    ctx.lineTo(x, y);
  }
  ctx.stroke();

  // Fill area under the line
  ctx.lineTo(width, height);
  ctx.lineTo(0, height);
  ctx.closePath();
  ctx.fillStyle = `${color}20`; // Add transparency
  ctx.fill();

  // Add gradient fill
  const gradient = ctx.createLinearGradient(0, 0, 0, height);
  gradient.addColorStop(0, `${color}30`);
  gradient.addColorStop(1, `${color}05`);

  ctx.fillStyle = gradient;

  // Redraw the fill with gradient
  ctx.beginPath();
  ctx.moveTo(0, initialY);
  for (let i = 1; i < data.length; i++) {
    const x = i * xStep;
    const y = height - ((data[i] - min) / range) * height;
    ctx.lineTo(x, y);
  }
  ctx.lineTo(width, height);
  ctx.lineTo(0, height);
  ctx.closePath();
  ctx.fill();
}

// Draw grid lines
function drawGrid(
  ctx: CanvasRenderingContext2D,
  width: number,
  height: number,
) {
  ctx.strokeStyle = "#ffffff10";
  ctx.lineWidth = 1;

  // Horizontal grid lines (5 lines)
  for (let i = 1; i < 5; i++) {
    const y = (height / 5) * i;
    ctx.beginPath();
    ctx.moveTo(0, y);
    ctx.lineTo(width, y);
    ctx.stroke();
  }

  // Vertical grid lines (6 lines for time periods)
  for (let i = 1; i < 6; i++) {
    const x = (width / 6) * i;
    ctx.beginPath();
    ctx.moveTo(x, 0);
    ctx.lineTo(x, height);
    ctx.stroke();
  }
}

// Draw price indicators
function drawPriceIndicators(
  ctx: CanvasRenderingContext2D,
  data: number[],
  width: number,
  height: number,
) {
  const max = Math.max(...data);
  const min = Math.min(...data);
  const current = data[data.length - 1];

  ctx.font = "12px Arial";
  ctx.fillStyle = "#ffffff80";

  // Current price (right side)
  ctx.textAlign = "right";
  ctx.fillText(
    `$${current.toLocaleString(undefined, { maximumFractionDigits: 0 })}`,
    width - 10,
    20,
  );

  // High price
  ctx.textAlign = "left";
  ctx.fillText(
    `High: $${max.toLocaleString(undefined, { maximumFractionDigits: 0 })}`,
    10,
    20,
  );

  // Low price
  ctx.fillText(
    `Low: $${min.toLocaleString(undefined, { maximumFractionDigits: 0 })}`,
    10,
    height - 10,
  );
}

export default TradingChart;
